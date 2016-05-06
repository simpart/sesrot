<?php
/**
 * @file   Scnf_check.php
 * @brief  config checker
 * @author simpart
 * @note   MIT License
 */
namespace fnc\cnf;

/*** function ***/
/**
 * @fn    chkGrp
 * @brief check group exists
 * @param (object) parsed yaml
 */
function chkGrp( $grp ) {
  try {
    if (array_values($grp) === $grp) {
      /* cnf-2-1 : group key required */
      throw new Ccmd_comErr( 'invalid config file(could not find group)' );
    }
    $cnt  = count( $grp );
    $keys = array_keys( $grp );
    if( 0 !== strcmp( $keys[$cnt-1], '__any__' ) ) {
      /* cnf-3-2 : __any__ key must specify bottom of group */
      throw new Ccmd_comErr( 'invalid config file(could not find __any__ group at bottom)' );
    }
    
  } catch ( Ccmd_comErr $ce ) {
    $ce->showError();
    exit();
  } catch ( Exception $e ) {
    throw new Exception(
      PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
      __FUNCTION__.'()'.$e->getMessage()
    );
  }
}

/**
 * @fn    chkSes
 * @brief check session exists
 * @param (object) parsed yaml
 */
function chkSes( $yml ) {
  try {
    foreach( $yml as $gkey => $gval ) {
      if( 0 === strcmp( $gkey, '__any__' ) ) {
        /* cnf-2-4 : check only string if __any__ group key */
        if( false === is_string($gval) ) {
          throw new Ccmd_comErr( 'invalid config file(__any__ contents)' );
        }
        continue;
      }
      
      if( false === array_key_exists( 'session', $gval ) ) {
        /* cnf-2-2 : session key required under group */
        throw new Ccmd_comErr( 'invalid config file(could not find session)' );
      }
    }
  } catch ( Ccmd_comErr $ce ) {
    $ce->showError();
    exit();
  } catch ( Exception $e ) {
    throw new Exception(
      PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
      __FUNCTION__.'()'.$e->getMessage()
    );
  }
}

/**
 * @fn    chkUrl
 * @brief check url
 * @param (object) parsed yaml
 */
function chkUrl( $yml ) {
  try {
    foreach( $yml as $gkey => $gval ) {
#      if( 0 === strcmp( $gkey, '__any__' ) ) {
#        /* cnf-2-4 : check only string if __any__ group key */
#        continue;
#      }
      if( false === array_key_exists( 'urlmap', $gval ) ) {
        /* cnf-2-3 : urlmap key required under group */
        throw new \err\CcomErr( 'invalid config file(could not find urlmap)' );
      }
      foreach( $gval['urlmap'] as $gurl ) {
        if( ( true !== file_exists( $gurl )) ||
            ( 0    !== strcmp( filetype( $gurl ), 'file' )) ) {
          /* cnf-3 : url contents must be file path that exists */
          throw new \err\CcomErr( 'invalid config file(could not find contents:'.$gurl.')' );
        }
      }
    }
  } catch ( \err\CcomErr $ce ) {
    $ce->showConts();
    exit();
  } catch ( Exception $e ) {
    throw new Exception(
      PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
      __FUNCTION__.'()'.$e->getMessage()
    );
  }
}


/* end of file */
