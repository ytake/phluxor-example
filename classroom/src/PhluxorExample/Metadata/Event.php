<?php
# Generated by the protocol buffer compiler.  DO NOT EDIT!
# source: event.proto

namespace PhluxorExample\Metadata;

class Event
{
    public static $is_initialized = false;

    public static function initOnce() {
        $pool = \Google\Protobuf\Internal\DescriptorPool::getGeneratedPool();

        if (static::$is_initialized == true) {
          return;
        }
        $pool->internalAddGeneratedFile(
            "\x0Ai\x0A\x0Bevent.proto\x12\x14PhluxorExample.Event\" \x0A\x0DClassFinished\x12\x0F\x0A\x07subject\x18\x01 \x01(\x09B\x1A\xE2\x02\x17PhluxorExample\\Metadatab\x06proto3"
        , true);

        static::$is_initialized = true;
    }
}

