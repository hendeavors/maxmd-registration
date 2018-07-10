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

        Person::create($response);

        return $response;
    }

    /**
     * @throws UnauthorizedAccessException
     * @return response object
     */
    public static function Check($request, $autoSendOTP = false)
    {
        $proof = new IdentityProof();

        $proof->VerifyAndAuthenticate($request, $autoSendOTP);

        $response = $proof->ToObject();

        Person::create($response);

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

    public static function VerifyCreditCard($request, \Closure $callBack = null)
    {
        $proof = new IdentityProof();

        $proof->VerifyCreditCard($request);

        $response = $proof->ToObject();

        $succeeds = $response->success;

        if( true === $succeeds && null !== $callBack ) {
            $provision = new Registration();

            return $callBack($provision, $response->personMeta->id);
        }

        return $response;
    }

    public static function VerifyAll($request, \Closure $callBack)
    {
        $originalRequest = $request;

        if( isset($request['creditCard']) ) {
            unset($request['creditCard']);
        }

        $response = static::VerifyMobile($request);

        if( null !== $response && true === $response->success) {
            $provision = new Registration();
            // if we pass we'll execute the provisioning callback
            return $callBack($provision, $response->personMeta->id);
        }

        $request = $originalRequest;

        if( isset($request['otp']) ) {
            unset($request['otp']);
        }

        // verify with credit card
        $response = static::VerifyCreditCard($request);

        if( null !== $response && true === $response->success) {
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
