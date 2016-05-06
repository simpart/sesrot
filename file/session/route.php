<?php
/** 
 * Copyright (c) 2016 simpart
 *  
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

/**
 * @file   route.php
 * @brief  session routing
 * @author simpart
 * @note   generated by trut
 */
namespace session;

/*** global ***/
@rep1

/*** function ***/
/**
 * @fn     getMatchGrp
 * @brief  get session status matched group
 * @param  (int) continue count
 * @return (string) matched group name
 * @return (null) not match any group
 */
function getMatchGrp( $cc ) {
    try {
        global $GsesTbl;
        $ret_val = null;
        $brk     = false;
        $hit     = false;
        
        foreach ( $GsesTbl as $grp => $elm ) {
            if ( 0 < $cc ) {
                $cc--;
                continue;
            }
            
            $brk = false;
            if ( null === $elm ) {
                $ret_val = $grp;
                break;
            }
            $hit = false;
            foreach ( $elm as $skey => $sval ) {
                if ( false === chkMatchSes( $skey, $sval ) ) {
                    $hit = false;
                    break;
                } else {
                    $hit = true;
                }
            }
            if ( true === $hit ) {
                $ret_val = $grp;
                break;
            }
        }
        if (0 === strcmp($ret_val, '__any__')) {
            return null;
        }
        return $ret_val;
    } catch ( \Exception $e ) {
        throw new \Exception (
            PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
            __FUNCTION__.'()'.$e->getMessage()
        );
    }
}


function chkMatchSes( $ckey, $cval ) {
    try {
        if ( false === isExists( $ckey ) ) {
            return false;
        }   
        if ( true === is_array( $cval ) ) {
            foreach ( $cval as $cval_elm ) {
                if ( 0 === strcmp( get($ckey), $cval_elm ) ) {
                    return true;
                }
            }
            return false;
        } else { 
            if ( 0 === strcmp( get($ckey), $cval ) ) {
                return true;
            } else { 
                return false;
            }   
        }   
        return false;
    } catch ( \Exception $e ) {
        throw new Exception (
            PHP_EOL.'ERR(File:'.basename(__FILE__).','.',Line:'.__line__.'):'.
            __FUNCTION__.'()'.$e->getMessage()
        );
    }
}

/* end of file */
