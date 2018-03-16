<?php

namespace Auth\Service;


use Auth\Entity\MemberInterface;
use Auth\Model\MemberModel;
use Contact\Entity\AddressInterface;
use Contact\Entity\ContactInterface;
use Contact\Entity\EmailAddressInterface;
use Contact\Entity\ImageInterface;
use Contact\Model\AddressModelInterface;
use Contact\Model\ContactModelInterface;
use Contact\Model\EmailAddressModelInterface;
use Contact\Model\ImageModelInterface;


class MemberService
{
    /**
     * @var MemberModel
     */
    protected $memberModel;

    /**
     * @var MemberInterface
     */
    protected $memberPrototype;

    /**
     * @var ContactModelInterface
     */
    protected $contactModel;

    /**
     * @var ContactInterface
     */
    protected $contactPrototype;

    /**
     * @var EmailAddressModelInterface
     */
    protected $emailAddressModel;

    /**
     * @var EmailAddressInterface
     */
    protected $emailAddressPrototype;

    /**
     * @var AddressModelInterface
     */
    protected $addressModel;

    /**
     * @var AddressInterface
     */
    protected $addressPrototype;

    /**
     * @var ImageModelInterface
     */
    protected $imageModel;

    /**
     * @var ImageInterface
     */
    protected $imagePrototype;

    /**
     * MemberService constructor.
     *
     * @param MemberModel $memberModel
     * @param MemberInterface $memberPrototype
     * @param ContactModelInterface $contactModel
     * @param ContactInterface $contactPrototype
     * @param EmailAddressModelInterface $emailAddressModel
     * @param EmailAddressInterface $emailAddressPrototype
     * @param AddressModelInterface $addressModel
     * @param AddressInterface $addressPrototype
     * @param ImageModelInterface $imageModel
     * @param ImageInterface $imagePrototype
     */
    public function __construct(
        MemberModel $memberModel,
        MemberInterface $memberPrototype,
        ContactModelInterface $contactModel,
        ContactInterface $contactPrototype,
        EmailAddressModelInterface $emailAddressModel,
        EmailAddressInterface $emailAddressPrototype,
        AddressModelInterface $addressModel,
        AddressInterface $addressPrototype,
        ImageModelInterface $imageModel,
        ImageInterface $imagePrototype
    )
    {
        $this->memberModel = $memberModel;
        $this->memberPrototype = $memberPrototype;
        $this->contactModel = $contactModel;
        $this->contactPrototype = $contactPrototype;
        $this->emailAddressModel = $emailAddressModel;
        $this->emailAddressPrototype = $emailAddressPrototype;
        $this->addressModel = $addressModel;
        $this->addressPrototype = $addressPrototype;
        $this->imageModel = $imageModel;
        $this->imagePrototype = $imagePrototype;
    }


    /**
     * Register a new member based on the information retrieved from LinkedIn
     *
     * @param array $memberProfileData
     * @param string $accessToken
     * @return MemberInterface
     */
    public function registerNewMember(array $memberProfileData, $accessToken)
    {
        $memberClass = get_class($this->memberPrototype);
        $newMember = new $memberClass(0, $memberProfileData['id'], $accessToken);
        $memberEntity = $this->memberModel->saveMember($newMember);

        $contactClass = get_class($this->contactPrototype);
        $newContact = new $contactClass(
            0,
            $memberEntity->getMemberId(),
            $memberProfileData['firstName'],
            $memberProfileData['lastName']
        );
        $contactEntity = $this->contactModel->saveContact($memberEntity->getMemberId(), $newContact);

        $contactEmailClass = get_class($this->emailAddressPrototype);
        $newContactEmail = new $contactEmailClass(
            0,
            $memberEntity->getMemberId(),
            $contactEntity->getContactId(),
            $memberProfileData['emailAddress'],
            true
        );
        $contactEmailEntity = $this->emailAddressModel->insertEmailAddress($contactEntity->getContactId(), $newContactEmail);

        $contactAddressClass = get_class($this->addressPrototype);
        $newContactAddress = new $contactAddressClass(
            0,
            $memberEntity->getMemberId(),
            $contactEntity->getContactId(),
            '', // street1
            '', // street2
            '', // postcode
            '', // city
            '', // province
            (isset ($memberProfileData['location']['country']['code']) ?
                strtoupper($memberProfileData['location']['country']['code']) :
                '')
        );
        $contactAddressEntity = $this->addressModel->saveAddress($contactEntity->getContactId(), $newContactAddress);

        $contactImageClass = get_class($this->imagePrototype);
        $newContactImage = new $contactImageClass(
            0,
            $memberEntity->getMemberId(),
            $contactEntity->getContactId(),
            $memberProfileData['pictureUrl'],
            true
        );
        $contactImageEntity = $this->imageModel->saveImage($contactEntity->getContactId(), $newContactImage);

        return $memberEntity;
    }

    /**
     * Update an existing member
     *
     * @param array $memberProfileData
     * @param string $accessToken
     * @return MemberInterface
     */
    public function updateMember(array $memberProfileData, $accessToken)
    {
        $member = $this->memberModel->getMemberByLinkedinId($memberProfileData['id']);
        $memberClass = get_class($this->memberPrototype);
        $memberEntity = new $memberClass(
            $member->getMemberId(),
            $member->getLinkedinId(),
            $accessToken
        );
        $this->memberModel->saveMember($memberEntity);
        return $memberEntity;
    }

}