<?php

namespace Eadesigndev\Pdfgenerator\Model\Email;

use Magento\Framework\Mail\MailMessageInterface;
use Zend\Mime\Mime;
use Zend\Mime\PartFactory;
use Zend\Mail\MessageFactory as MailFactory;
use Zend\Mime\MessageFactory as MimeFactory;
use Zend\Mime\Part;

/**
 * Class Message
 * @package Eadesigndev\Pdfgenerator\Model\Email
 * @deprecated
 */
class Message extends \Magento\Framework\Mail\Message implements MailMessageInterface
{

    private $partFactory;

    private $mimeMessageFactory;

    protected $zendMessage;

    private $attachment;

    private $messageType = self::TYPE_TEXT;

    public function __construct(
        PartFactory $partFactory,
        MimeFactory $mimeMessageFactory,
        $charset = 'utf-8'
    ) {
        $this->partFactory = $partFactory;
        $this->mimeMessageFactory = $mimeMessageFactory;
        $this->zendMessage = MailFactory::getInstance();
        $this->zendMessage->setEncoding($charset);
    }

    public function setBodyAttachment($content, $fileName)
    {
        $attachmentPart = $this->partFactory->create();

        $attachmentPart->setContent($content)
            ->setType(Mime::TYPE_OCTETSTREAM)
            ->setEncoding(Mime::ENCODING_BASE64)
            ->setFileName($fileName)
            ->setDisposition(Mime::DISPOSITION_ATTACHMENT);

        $this->attachment = $attachmentPart;
        return $this;
    }

    public function setMessageType($type)
    {
        $this->messageType = $type;
        return $this;
    }

    public function setBody($body)
    {
        if (is_string($body) && $this->messageType === MailMessageInterface::TYPE_HTML) {
            $body = self::createHtmlMimeFromString($body);
        }

        $attachment = $this->attachment;
        if (isset($attachment)) {
            $body->addPart($attachment);
        }

        $this->zendMessage->setBody($body);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $this->zendMessage->setSubject($subject);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        return $this->zendMessage->getSubject();
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        return $this->zendMessage->getBody();
    }

    /**
     * {@inheritdoc}
     */
    public function setFromAddress($fromAddress, $fromName = null)
    {
        $this->zendMessage->setFrom($fromAddress, $fromName);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addTo($toAddress)
    {
        $this->zendMessage->addTo($toAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addCc($ccAddress)
    {
        $this->zendMessage->addCc($ccAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function addBcc($bccAddress)
    {
        $this->zendMessage->addBcc($bccAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setReplyTo($replyToAddress)
    {
        $this->zendMessage->setReplyTo($replyToAddress);
        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getRawMessage()
    {
        return $this->zendMessage->toString();
    }

    private function createHtmlMimeFromString($htmlBody)
    {
        $htmlPart = $this->partFactory->create(['content' => $htmlBody]);
        $htmlPart->setCharset($this->zendMessage->getEncoding());
        $htmlPart->setType(Mime::TYPE_HTML);
        $mimeMessage = $this->mimeMessageFactory->create();
        $mimeMessage->addPart($htmlPart);
        return $mimeMessage;
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyHtml($html)
    {
        $this->setMessageType(self::TYPE_HTML);
        return $this->setBody($html);
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyText($text)
    {
        $this->setMessageType(self::TYPE_TEXT);
        return $this->setBody($text);
    }
}
