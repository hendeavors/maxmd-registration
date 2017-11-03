<?php

namespace Endeavors\MaxMD\Registration\Person;

use Endeavors\MaxMD\Proofing\IdentityProof;
use Endeavors\MaxMD\Registration\Patient\Registration as PatientRegistration;

/**
 * Patient Registration api
 */
final class Patient
{
    /**
     * @throws UnauthorizedAccessException
     * @return response object
     */
    public static function Proof($request)
    {
        $proof = new IdentityProof();
        
        $proof->Verify($request);
        
        $response = $proof->ToObject();

        return $response;
    }

    /**
     * A case where we may want to verify the number
     * And provision with a username and password
     * @throws UnauthorizedAccessException
     * @return response object|callback
     */
    public static function VerifyMobile($request, \Closure $callBack = null)
    {
        $proof = new IdentityProof();
        
        $proof->VerifyOneTimePassword($request);
        
        $response = $proof->ToObject();

        $succeeds = $response->success;
        
        if( $succeeds && null !== $callBack ) {
            $provision = new Registration();
            // if we pass we'll execute the provisioning callback
            return $callBack($provision, $response->personMeta->id);
        }

        return $response;
    }

    /**
     * The mobile number must be verified first
     * @throws UnauthorizedAccessException
     * @return response object|callback
     */
    public static function Provision($request, \Closure $callBack = null)
    {
        $response = static::Proof($request);

        $succeeds = $response->success;

        if( $succeeds && $response->verificationStatus === "LoA3Certified" ) {
            $provision = new Registration();
            // if we pass we'll execute the provisioning callback
            return $callBack($provision, $response->personMeta->id);
        }

        return $response;
    }
    
    /**
     * @throws UnauthorizedAccessException
     */
    public static function Passes($request)
    {
        $proof = new IdentityProof();
        
        $proof->Verify($request);
        
        return $proof->ToObject()->success;
    }

    

    /**
     * @throws UnauthorizedAccessException
     */
    public static function Fails($request)
    {
        return ! static::Passes($request);
    }
}