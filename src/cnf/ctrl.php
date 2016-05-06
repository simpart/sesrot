<?php
/**
 * @file   Scnf_ctrl.php
 * @brief  config reader
 * @author simpart
 * @note   MIT License
 */
namespace fnc\cnf;

/*** require ***/
require_once( __DIR__.'/check.php' );

/*** class ***/
/**
 * @fn     read
 * @brief  parse yaml and check
 * @param  (string) path to config file
 * @return (Ccnf_info) config object
 */
function read( $cpath ) {
    try {
        /* cnf-1 : read yaml file */
        $yml = yaml_parse_file( $cpath );
        if( false === $yml ) {
            throw new \err\ComErr( 'invalid config file(is not yaml)' );
        }
        /* check group */
        chkGrp( $yml );
        
        /* check session */
        #chkSes( $yml );
        
        /* check url */
        chkUrl( $yml );
        
        return new Info( $yml );
    } catch( Exception $e ) {
        throw $e;
    }
}
/* end of file */
