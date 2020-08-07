<?php

namespace Noardcode\MicrosoftGraph\ValueObjects\Users;

/**
 * Class User
 * @package Noardcode\MicrosoftGraph\ValueObjects
 */
class User
{
    /**
     * The unique identifier for the user.
     *
     * @var mixed|string
     */
    public string $id;

    /**
     * The Microsoft Graph API returns an array of (string) phone numbers but it contains none or a maximum of
     * one phone number. As described in the user model documentation.
     *
     * More information:
     * https://docs.microsoft.com/en-US/graph/api/resources/user?view=graph-rest-1.0
     *
     * @var mixed|string|null
     */
    public ?string $businessPhone;

    /**
     * @var mixed|string
     */
    public string $displayName;

    /**
     * @var mixed|string|null
     */
    public ?string $givenName;

    /**
     * @var mixed|string|null
     */
    public ?string $jobTitle;

    /**
     * @var mixed|string|null
     */
    public ?string $mail;

    /**
     * The primary mobile phone of the user.
     *
     * @var string|null
     */
    public ?string $mobilePhone;

    /**
     * @var mixed|string|null
     */
    public ?string $officeLocation;

    /**
     * The preferred language for the user.
     * Should follow ISO 639-1 Code; for example "en-US".
     *
     * @var string|null
     */
    public ?string $preferredLanguage;

    /**
     * @var mixed|string|null
     */
    public ?string $surname;

    /**
     * UPN of user (RFC 822).
     *
     * @var mixed|string|null
     */
    public ?string $userPrincipalName;

    /**
     * User constructor.
     * @param array $response
     */
    public function __construct(array $response)
    {
        $this->id = $response['id'];
        $this->businessPhone = isset($response['businessPhones'][0]) ? $response['businessPhones'][0] : null;
        $this->displayName = $response['displayName'];
        $this->givenName = $response['givenName'];
        $this->jobTitle = $response['jobTitle'];
        $this->mail = $response['mail'];
        $this->officeLocation = $response['officeLocation'];
        $this->preferredLanguage = $response['preferredLanguage'];
        $this->surname = $response['surname'];
        $this->userPrincipalName = $response['userPrincipalName'];
    }

    /**
     * @return mixed|string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return mixed|string|null
     */
    public function getBusinessPhone(): ?string
    {
        return $this->businessPhone;
    }

    /**
     * @return mixed|string
     */
    public function getDisplayName(): string
    {
        return $this->displayName;
    }

    /**
     * @return mixed|string|null
     */
    public function getGivenName(): ?string
    {
        return $this->givenName;
    }

    /**
     * @return mixed|string|null
     */
    public function getJobTitle(): ?string
    {
        return $this->jobTitle;
    }

    /**
     * @return mixed|string|null
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * @return string|null
     */
    public function getMobilePhone(): ?string
    {
        return $this->mobilePhone;
    }

    /**
     * @return mixed|string|null
     */
    public function getOfficeLocation(): ?string
    {
        return $this->officeLocation;
    }

    /**
     * @return string|null
     */
    public function getPreferredLanguage(): ?string
    {
        return $this->preferredLanguage;
    }

    /**
     * @return mixed|string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @return mixed|string|null
     */
    public function getUserPrincipalName(): ?string
    {
        return $this->userPrincipalName;
    }
}
