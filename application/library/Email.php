<?php

/**
 * Created by PhpStorm.
 * User: EtimEsuOyoIta
 * Date: 8/13/17
 * Time: 9:00 AM
 */
class Email extends View
{

    /**
     * Email constructor.
     */
    public function __construct($filename)
    {
        $this->load($filename, 'email');
    }

    public function preparedEmail($from, $fromEmail, $to, $toEmail, $subject, $content)
    {
        $tplContents = file_get_contents($this->file);
        $user = AppService::currentUser();
        $subject = htmlspecialchars_decode(htmlentities($subject));
        $content = htmlspecialchars_decode(htmlentities($content));
        $replacements = [
            '__DATE__' => date("g:iA, l, F j, Y"),
            '__IMAGES__' => str_replace(ROOT, URL, $this->folder. '\images'),
            '__FROM__' => $from,
            '__FROM_EMAIL__' => $fromEmail,
            '__TO__' => (isset($to) && strlen($to)) > 0 ? $to : $toEmail,
            '__TO_EMAIL__' => $toEmail,
            '__SUBJECT__' => $subject,
            '__CONTENT__' => $content,
            '__SIGNATURE__' => isset($user) ? $user->getEmailSignature() : ""
        ];
        foreach ($replacements as $index => $replacement) {
            $tplContents = str_replace($index, $replacement, $tplContents);
        }
        return $tplContents;
    }
}
