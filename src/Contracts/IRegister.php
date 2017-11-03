<?php

namespace Endeavors\MaxMD\Registration\Contracts;

interface IRegister
{
    /**
     * @param string directDomain
     * @param IDProofedIndividual https://evalapi.max.md:8445/registration/#Patient_IDProofedIndividual
     * @param string directUsername
     * @param string directPassword
     * @return AddUsersResponseV2Type https://evalapi.max.md:8445/registration/#Patient_AddUsersResponseV2Type
     */
    function ProvisionIDProofedPatient($directDomain, $request, $directUsername, $directPassword);
    
    /**
     * @param String directDomain
     * @param IDProofedIndividual request https://evalapi.max.md:8445/registration/#Patient_IDProofedIndividual
     * @return GetPatientAddressesResponseType https://evalapi.max.md:8445/registration/#Patient_GetPatientAddressesResponseType
     */
    function GetPatientAddressByMeta($directDomain, $request);

    /**
     * @param string directDomain
     * @param string directUsername
     * @return GetPatientAddressesResponseType https://evalapi.max.md:8445/registration/#Patient_GetPatientAddressesResponseType
     */
    function GetPatientAddressByUsername ($directDomain, $directUsername);

    /**
     * return a raw response from the service
     */
    function Raw();
}