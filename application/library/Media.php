<?php

abstract class Media 
{

	const Root = MEDIA_ROOT_SRC;
	const URL = MEDIA_INDEX_URL;

	/**
	 * Return an array of all members of a given directory.
	 * @param string $dir
	 * @return array
	 */
	public static function dir($dir = self::Root) {
		// just declare the folder first
		// echo $dir;
		// Open a known directory, and proceed to read its contents
		// if the last part of a file is not a slash, add one
		$dir = $dir. ((substr($dir, -1) == '/') ? '' : '/');
		// prepare the final array
		$folders = array();
		$notTaken = array('.', '..', '.DS_Store');
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				$n = 0;
				while (($file = readdir($dh)) !== false) {
					if (!in_array($file, $notTaken)) {
						$fInfo = finfo_open(FILEINFO_MIME_TYPE);
						if (!is_dir($dir. $file)) {
							$list = explode('.', $file);
							$ext = strtoupper($list[count($list) - 1]);
							$mime = finfo_file($fInfo, $dir . $file);
							$otherMime = pathinfo($dir. $file, PATHINFO_EXTENSION);
							$mp = explode('/', $mime);
							$mediatypes = array('image', 'video', 'audio');
							$isMediaFile = (in_array($mp[0], $mediatypes)) ? true : false;
							$url = self::srcToUrl($dir. $file);
						}
						$folders[] = ((!is_dir($dir . $file) ? array(
							"filename" => $file,
							"absolute" => $dir . $file,
							"url" => $url,
							"filetype" => filetype($dir . $file),
							"mimetype" => $mime,
							"mediatype" => $mp[0],
							"filesize" => self::sizeInUnits((int) filesize($dir . $file)),
							"extension" => $ext
						) : array(
							"filename" => $file,
							"absolute" => $dir . $file,
							"url" => self::srcToUrl($dir. $file),
							"filetype" => filetype($dir . $file),
							"descendants" => self::dir($dir . $file),
						)));
						finfo_close($fInfo);
					}
				}
				closedir($dh);
			}
		}
		return $folders;
	}

	/**
	 * Reads the information associated with a file or directory, or returns false where that is not possible.
	 * @param $src
	 * @return array|bool|mixed
	 */
	public static function fileFromSrc($src) {
		if (is_readable($src)) {
			$file = basename($src);
			if (is_file($src)) {

				$finfo = finfo_open(FILEINFO_MIME_TYPE);
				$list = explode('.', $src, 2);
				$ext = strtoupper($list[1]);
				$mime = finfo_file($finfo, $src);
				$mp = explode('/', $mime);
				$url = self::srcToUrl($src);

				$data = [
					"filename" => $file,
					"absolute" => $src,
					"url" => $url,
					"filetype" => filetype($src),
					"mimetype" => $mime,
					"mediatype" => $mp[0],
					"filesize" => self::sizeInUnits((int) filesize($src)),
					"extension" => $ext
				];

				finfo_close($finfo);
			} elseif (is_dir($src)) {
				$data = [
					"filename" => $file,
					"absolute" => $src,
					"url" => self::srcToUrl($src),
					"filetype" => filetype($src),
					"descendants" => self::dir($src)
				];
			}
		} else {
			$data = false;
		}

		if ($data) {
			$series = json_decode(json_encode($data));
			return $series;
		} else {
			return $data;
		}
	}

	/**
	 * Takes the feed from a file upload input and saves the files sent.
	 * @param $inputControl
	 * @return array
	 */
	public static function upload($inputControl) {
		$input = $_FILES[$inputControl];
		
		$name = $input['name'];
		$tmpName = $input['tmp_name'];
		$type = $input['type'];
		$error = $input['error'];
		$size = $input['size'];
		
		$saveErrors = array();
		$saveResult = array();
		
		if (is_array($name)) { // multiple files 
			foreach ($name as $key => $value) {
				$thisName = $name[$key];
				$thisTmpName = $tmpName[$key];
				$thisType = $type[$key];
				$thisError = $error[$key];
				$thisSize = $size[$key];
				
				$mp = explode("/", $thisType);
				$list = explode(".", $thisName);
				$ext = strtolower($list[(count($list) - 1)]);
				
				$src = self::Root. "/$thisType";
				if (!file_exists($src)) mkdir($src, 0777, true); // create the directory where it does not exist
				@chmod($src, 0777); // ...and then set it up to ensure it can be written to.
				
				$srcX = $src;
				$src .= "/". strtolower($thisName);
			
				if (move_uploaded_file($thisTmpName, $src)) {
					$saveResult['messages'][] = "File $thisName successfully uploaded. <br>";
					@chmod($srcX, 0755); // restore default file permissions
					$saveResult['srcs'][] = $src;
				} else {
					$saveErrors['errors'][] = "File $thisName did not upload.";
				}
			}
		} else { // single file
			$mp = explode("/", $type);
			$list = explode(".", $name);
			$ext = strtolower($list[(count($list) - 1)]);
			
			$src = self::Root. "/$type";
			if (!file_exists($src)) mkdir($src, 0777, true); // * To ENSURE that the file exists *
			@chmod($src, 0777); // * We are set to move files *
			
			$src .= "/". strtolower($name);		
			
			if (move_uploaded_file($tmpName, $src)) {
				$saveResult['messages'][] = "File $name successfully uploaded. <br>";
				@chmod($src, 0455);
				$saveResult['srcs'][] = $src;
			} else {
				$saveErrors['errors'][] = "File $name did not upload.";
			}
		}
		
		return (empty($saveErrors) ? $saveResult : $saveErrors);
	}

	/**
	 * Convert a canonical file address to its equivalent Media URL.
	 * @param $src
	 * @return mixed
	 */
	public static function srcToUrl($src) {

		// replace Root with URL
		$src = str_replace("\\", "/", $src);
		return str_ireplace(self::Root, self::URL, $src);

	}

	/**
	 * Convert a Media URL to its equivalent canonical file address.
	 * @param $url
	 * @return mixed
	 */
	public static function urlToSrc($url) {

		// replace URL with Root
		return str_ireplace(self::URL, self::Root, $url);

	}

	/**
	 * Return the size of a file, in the correct units.
	 * @param int $size
	 * @return int|string
	 */
	public static function sizeInUnits($size = 0) {

		if ($size > 0) {
			$units = array(' ', ' K', ' M', ' G', ' T');
			$order = floor(log($size, 1024));
			$power = pow(1024, $order);
			$value = round(($size / $power), 2);

			return $value. $units[$order]. (($order == 0) ? 'bytes' : 'B');
		} else {
			return 0;
		}

	}

	/**
	 * Delete and destroy a file.
	 * @param $src
	 */
	public static function delete($src) {
		if (is_dir($src)) {
			rmdir($src);
		} else {
			unlink($src);
		}
	}

}
