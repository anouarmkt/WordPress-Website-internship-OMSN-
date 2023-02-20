<?php
namespace Firebase\JWT;

defined('ABSPATH') || exit;


if ( !class_exists( 'Firebase\JWT\SignatureInvalidException' ) ) {
    class SignatureInvalidException extends \UnexpectedValueException
    {
    }
}
