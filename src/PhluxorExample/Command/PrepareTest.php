<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: command.proto

namespace PhluxorExample\Command;

use Google\Protobuf\Internal\GPBType;
use Google\Protobuf\Internal\RepeatedField;
use Google\Protobuf\Internal\GPBUtil;

/**
 * Generated from protobuf message <code>PhluxorExample.Command.PrepareTest</code>
 */
class PrepareTest extends \Google\Protobuf\Internal\Message
{
    /**
     * Generated from protobuf field <code>string subject = 1;</code>
     */
    protected $subject = '';

    /**
     * Constructor.
     *
     * @param array $data {
     *     Optional. Data for populating the Message object.
     *
     *     @type string $subject
     * }
     */
    public function __construct($data = NULL) {
        \PhluxorExample\Metadata\Command::initOnce();
        parent::__construct($data);
    }

    /**
     * Generated from protobuf field <code>string subject = 1;</code>
     * @return string
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * Generated from protobuf field <code>string subject = 1;</code>
     * @param string $var
     * @return $this
     */
    public function setSubject($var)
    {
        GPBUtil::checkString($var, True);
        $this->subject = $var;

        return $this;
    }

}

