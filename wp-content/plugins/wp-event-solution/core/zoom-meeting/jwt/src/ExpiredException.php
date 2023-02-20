<?php
namespace Firebase\JWT;

defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Firebase\JWT\ExpiredException' ) ) {
    class ExpiredException extends \UnexpectedValueException {
    }

}
