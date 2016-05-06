<?php
/**
 * @file   SesRot.php
 * @brief  half line function
 * @author simpart
 * @note   MIT License
 */
namespace fnc\gen\SesRot\src;

/*** require ***/
require_once(__DIR__.'/func.php');

class SesRot implements \fnc\gen\InfGen
{
    private $cnf = null;
    private $prm = null;
    /**
     * set Generator Object
     *
     * @param $g : generator object
     */
    public function __construct($c, $p) {
        try {
            $this->cnf = $c;
            $this->prm = $p;
        } catch (\Exception $e) {
            throw $e;
        }
    }
    
    /**
     * start php source generate
     */
    public function generate() {
        try {
            /* fnc-3-4 : generate controller */
            rotCtrl( $this->prm );
 
            /* fnc-3-5 : generate group */
            $cnf_obj = new cnf\Info($this->cnf);
            while( null !== ($grp = $cnf_obj->getNextGrp()) ) {
                if (0 !== strcmp($grp, '__any__')) {
                    rotGrp( $grp,
                            $this->prm->getOutput(),
                            $cnf_obj
                          );
                }
            }
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
/* end of file */
