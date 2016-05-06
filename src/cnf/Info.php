<?php
/**
 * @file   Ccnf_info.php
 * @brief  Config Information
 * @author simpart
 * @note   MIT License
 */
namespace fnc\gen\SesRot\src\cnf;

/**
 * @class Info
 * @brief config information
 */
class Info {
    private $config = null;
    private $grpidx = 0;
    
    /**
     * set config
     * 
     * @param (array) parsed yaml
     */
    function __construct( $cnf ) {
        try {
            $this->config = $cnf;
        } catch( \Exception $e ) {
            throw new \Exception(
                PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
                get_class($this).'->'.__FUNCTION__.'()'.$e->getMessage()
            );
        }
    }
  
    /**
     * get next group
     * 
     * @return (array) group
     * @return (null) end of group
     */
    public function getNextGrp() {
        try {
            $loop = 0;
            foreach( $this->config as $grp_nm => $grp_val ) {
                if( $loop === $this->grpidx ) {
                    $this->grpidx++;
                    return $grp_nm;
                }
                $loop++;
            }
            $this->grpidx = 0;
            return null;
        } catch ( \Exception $e ) {
            throw new \Exception(
                PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
                get_class($this).'->'.__FUNCTION__.'()'.$e->getMessage()
            );
        }
    }
    
    /**
     * get group contents
     *
     * @param $gnm : (string) group name
     * @return (array) : group contents
     */
    public function getGroup( $gnm ) {
        try {
            if (false === array_key_exists($gnm, $this->config)) {
                return null;
            }
            return $this->config[$gnm];
        } catch (\Exception $e) {
            throw new \Exception(
                PHP_EOL.'ERR(File:'.basename(__FILE__).',Line:'.__line__.'):'.
                get_class($this).'->'.__FUNCTION__.'()'.$e->getMessage()
            );
        }
    }
    
    /**
     * get session contetns from yaml
     * 
     * @param $gnm : (string) group name
     * @return (array) session contents
     */
    public function getSession( $gnm ) {
        try {
            foreach( $this->config as $grp_nm => $grp_val ) {
                if( 0 === strcmp( $grp_nm, $gnm ) ) {
                    if( 0 === strcmp( $grp_nm, '__any__' ) ) {
                        return null;
                    } else {
                        return $grp_val['session'];
                    }
                }
            }
            return null;
        } catch ( \Exception $e ) {
            throw new \Exception(
                PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
                get_class($this).'->'.__FUNCTION__.'()'.$e->getMessage()
            );
        }
    }
    
    /**
     * get uri map
     *
     * @param  gnm (string) group name
     * @return (array) url map
     */
    public function getUrimap( $gnm ) {
        try {
            foreach( $this->config as $grp_nm => $grp_val ) {
                if( 0 === strcmp( $grp_nm, $gnm ) ) {
                    //if( 0 === strcmp( $grp_nm, '__any__' ) ) {
                    //    return array( '__any__' => $grp_val );
                    //} else {
                        return $grp_val['urlmap'];
                    //}
                }
            }
            return null;
        } catch ( \Exception $e ) {
            throw new \Exception(
                PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
                get_class($this).'->'.__FUNCTION__.'()'.$e->getMessage()
            );
        }
    }
}
/* end of file */
