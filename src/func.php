<?php
/**
 * @file   SesRot.php
 * @brief  half line function
 * @author simpart
 * @note   MIT License
 */
namespace fnc\gen\SesRot\src;

/*** function ***/
/**
 * @fn    rotCtrl
 * @brief generate routing controller
 */
function rotCtrl( $gen ) {
    try {
        $cnf_obj = new cnf\Info($gen->getConf());
        /* routing */
        $rot = file_get_contents(
                   __DIR__ . '/../file/route.php'
               );
        if ( false === $rot ) { 
            throw new \err\ComErr( 
                'failed read route.php file',
                'please check ' . __DIR__ . '/../file/route.php'
            );
        }
        $gen_rep = $cnf_obj->getUrimap('__any__');
        foreach($gen_rep as $rep_elm) {
            $rot_code = str_replace('@gen1', $rep_elm, $rot );
            break;
        }

        $ret = file_put_contents( $gen->getOutput().'route.php', $rot_code );
        if ( false === $ret ) {
            throw new \err\ComErr( 
                'failed create route.php file',
                'please check ' . $fnc->getOutput()
            );
        }
        /* common */
        $ret = copy(
                   __DIR__ . '/../file/common.php',
                   $gen->getOutput().'common.php'
               );
        if ( false === $ret ) {
            throw new \err\ComErr(
                'failed create common.php file',
                'please check ' . $gen->getOutput()
            );
        }
        /* session */
        $ret = mkdir( $gen->getOutput().'session' );
        if ( false === $ret ) {
            throw new \err\ComErr(
                'failed create session directory',
                'please check ' . $gen->getOutput()
            );
        }
        $ret = copy(
                   __DIR__   . '/../file/session/crud.php',
                   $gen->getOutput() . 'session/crud.php'
               );
        if ( false === $ret ) {
            throw new \err\ComErr( 
                'failed create crud.php file',
                'please check ' . $gen->getOutput()
            );
        }
        $ses_rot = file_get_contents ( 
                       __DIR__  . '/../file/session/route.php'
                   );
        if ( false === $ses_rot ) {
            throw new \err\ComErr(
                'failed read session/route.php file',
                __DIR__  . '/../file/session/route.php'
            );
        }
        $grp     = null;
        $ses_lst = array();
        while ( null !== ( $grp = $cnf_obj->getNextGrp() ) ) {
            $ses_lst[$grp] = $cnf_obj->getSession( $grp );
        }
        $ses_tbl      = getArrayCode( $ses_lst );
        $rep1         = '$GsesTbl = '.$ses_tbl;
        $ses_rot_code = str_replace( '@rep1', $rep1, $ses_rot );
        $ret          = file_put_contents( 
                            $gen->getOutput().'session'.DIRECTORY_SEPARATOR.'route.php',
                            $ses_rot_code
                        );
        if ( false === $ret ) {
            throw new \err\ComErr(
                'failed create session'.DIRECTORY_SEPARATOR.'route.php file',
                'please check ' . $gen->getOutput() . 'session'
            );
        }
    } catch ( \Exception $e ) {
        throw $e;
    }
}

/**
 * @fn    getRotGrp
 * @brief get route group 
 * @param $grp : (string) group name
 * @param $out : (string) output directory
 * @param $cnf : (object) cnf\Info obuject
 */
function rotGrp( $grp, $out, $cnf ) {
    try {
        $ret = null;
        $ret = mkdir( $out . $grp );
        if ( false === $ret ) {
            throw new \err\gen\GenErr( 
                'failed create ' . $grp . ' directory',
                'please check ' . $out
            );
        }
        // set group
        $grp_ctl = file_get_contents (
                       __DIR__ . '/../file/group/SgrpCtrl.php'
                   );
        if ( false === $grp_ctl ) {
            throw new \err\gen\GenErr( 
                'could not read '.$grp.'/SgrpCtrl.php file',
                'please check ' . __DIR__ . '/../file/group/SgrpCtrl.php'
            );
        }
        $grp_ctl_code = str_replace( '@gen1', $grp, $grp_ctl ); 
        $grp_ctl_code = str_replace( 
                            '@gen2',
                            getArrayCode( $cnf->getUrimap( $grp ) ),
                            $grp_ctl_code
                        );
        $ret = file_put_contents( $out.$grp.'/SgrpCtrl.php', $grp_ctl_code );
        if ( false === $ret ) {
            throw new \err\gen\GenErr(
                'failed create '.$grp.'/SgrpCtrl.php file',
                'please check ' . $out . $grp
            );
        }
    } catch ( \Exception $e ) {
        throw $e;
    }
}

function getArrayCode( $ary, $cnt=0 ) {
    try {
        $isarr = false;
        if ( false === is_array( $ary ) ) {
            throw new Exception( 'invalid parameter' );
        }
        $ret_str = 'array(';
        if ( 0 === $cnt ) {
            $ret_str .= PHP_EOL;
        }
        if ( array_values( $ary ) === $ary ) {
            foreach ( $ary as $val ) {
                if ( null === $val ) {
                    $ret_str .= 'null';
                } else if ( true === is_array( $val ) ) {
                    $isarr    = true; 
                    $ret_str .= ' '.getArrayCode( $val, $cnt+1 );
                } else {
                    if ( true === is_string( $val ) ) {
                        $ret_str .= '\''. $val .'\'';
                    } else {
                        $ret_str .= $val;
                    }
                }
                $ret_str .= ',';
                if ( (0 === $cnt) && (true === $isarr) ) {
                    $ret_str .= PHP_EOL;
                    $isarr    = false;
                }
            }
        } else {
            foreach ( $ary as $key => $val ) {
                if ( 0 === $cnt ) {
                    $ret_str .= '    ';
                }
                $ret_str .= '\''. $key .'\' =>';
                if ( null === $val ) {
                    $ret_str .= ' null';
                } else if ( true === is_array( $val ) ) {
                    $isarr    = true;
                    $ret_str .= ' '.getArrayCode( $val, $cnt+1 );
                } else {
                    if ( true === is_string( $val ) ) {
                        $ret_str .= '\''. $val .'\'';
                    } else {
                        $ret_str .= $val;
                    }
                }
                $ret_str .= ',';
                if ( (0 === $cnt) && (true === $isarr) ) {
                    $ret_str .= PHP_EOL;
                    $isarr    = false;
                }
            }
        }
        
        if ( 0 === $cnt ) {
            $ret_str .= PHP_EOL.');';
        } else {
            $ret_str .= ')';
        }
        return $ret_str;
    } catch ( \Exception $e ) {
        throw $e;
    }
}
/* end of file */
