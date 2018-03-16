<?php

namespace Contact\Controller;

use Contact\Model\ContactModelInterface;
use Contact\Model\EmailAddressModelInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ContactController extends AbstractActionController
{
    /**
     * @var ContactModelInterface
     */
    private $contactModel;

    /**
     * @var EmailAddressModelInterface
     */
    private $emailAddressModel;

    /**
     * ContactController constructor.
     *
     * @param ContactModelInterface $contactModel
     * @param EmailAddressModelInterface $emailAddressModel
     */
    public function __construct(
        ContactModelInterface $contactModel,
        EmailAddressModelInterface $emailAddressModel
    )
    {
        $this->contactModel = $contactModel;
        $this->emailAddressModel = $emailAddressModel;
    }

    public function indexAction()
    {
        return $this->redirect()->toRoute('contact/overview', ['page' => 1]);
    }

    public function overviewAction()
    {
        $page = $this->params()->fromRoute('page', 1);
        $memberId = $this->identity()->getMemberId();

        $contacts = $this->contactModel->fetchAllContacts($memberId);

        $viewModel = [
            'contacts' => $contacts,
            'page' => $page,
        ];

        return new ViewModel($viewModel);
    }
    public function editAction()
    {

    }
}