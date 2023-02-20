<?php
namespace Firebase\JWT;

defined('ABSPATH') || exit;


if ( !class_exists( 'Firebase\JWT\BeforeValidException' ) ) {
    class BeforeValidException extends \UnexpectedValueException
    {
    }
}

