<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateRecipientRequest;
use App\Http\Requests\UpdateRecipientRequest;
use App\Repositories\RecipientRepository;
use App\Http\Controllers\AppBaseController;
use Illuminate\Http\Request;
use Flash;
use Prettus\Repository\Criteria\RequestCriteria;
use Response;
use App\Repositories\GroupRepository;

class RecipientController extends AppBaseController
{
    /** @var  RecipientRepository */
    private $recipientRepository;
    private $groupRepository;

    public function __construct(RecipientRepository $recipientRepo, GroupRepository $groupRepo)
    {
        $this->recipientRepository = $recipientRepo;
        $this->groupRepository = $groupRepo;
    }

    /**
     * Display a listing of the Recipient.
     *
     * @param Request $request
     * @return Response
     */
    public function index(Request $request)
    {
        $this->recipientRepository->pushCriteria(new RequestCriteria($request));
        $recipients = $this->recipientRepository->all();

        return view('recipients.index')
            ->with('recipients', $recipients);
    }

    /**
     * Show the form for creating a new Recipient.
     *
     * @return Response
     */
    public function create(Request $request)
    {
        $this->groupRepository->pushCriteria(new RequestCriteria($request));
        $groups = $this->groupRepository->pluck('name', 'id');

        return view('recipients.create')->with('groups', $groups);
    }

    /**
     * Store a newly created Recipient in storage.
     *
     * @param CreateRecipientRequest $request
     *
     * @return Response
     */
    public function store(CreateRecipientRequest $request)
    {
        $input = $request->all();

        $recipient = $this->recipientRepository->create($input);

        Flash::success('Recipient saved successfully.');

        return redirect(route('recipients.index'));
    }

    /**
     * Display the specified Recipient.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function show($id)
    {
        $recipient = $this->recipientRepository->findWithoutFail($id);

        if (empty($recipient)) {
            Flash::error('Recipient not found');

            return redirect(route('recipients.index'));
        }

        return view('recipients.show')->with('recipient', $recipient);
    }

    /**
     * Show the form for editing the specified Recipient.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function edit($id, Request $request)
    {
        $recipient = $this->recipientRepository->findWithoutFail($id);

        if (empty($recipient)) {
            Flash::error('Recipient not found');

            return redirect(route('recipients.index'));
        }
        $this->groupRepository->pushCriteria(new RequestCriteria($request));
        $groups = $this->groupRepository->pluck('name', 'id');

        return view('recipients.edit')->with('recipient', $recipient)->with('groups', $groups);
    }

    /**
     * Update the specified Recipient in storage.
     *
     * @param  int              $id
     * @param UpdateRecipientRequest $request
     *
     * @return Response
     */
    public function update($id, UpdateRecipientRequest $request)
    {
        $recipient = $this->recipientRepository->findWithoutFail($id);

        if (empty($recipient)) {
            Flash::error('Recipient not found');

            return redirect(route('recipients.index'));
        }

        $recipient = $this->recipientRepository->update($request->all(), $id);

        Flash::success('Recipient updated successfully.');

        return redirect(route('recipients.index'));
    }

    /**
     * Remove the specified Recipient from storage.
     *
     * @param  int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $recipient = $this->recipientRepository->findWithoutFail($id);

        if (empty($recipient)) {
            Flash::error('Recipient not found');

            return redirect(route('recipients.index'));
        }

        $this->recipientRepository->delete($id);

        Flash::success('Recipient deleted successfully.');

        return redirect(route('recipients.index'));
    }
}
