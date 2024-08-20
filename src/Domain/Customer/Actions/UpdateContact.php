<?php

declare(strict_types=1);

namespace Domain\Customer\Actions;

use Domain\Customer\Models\Contact;
use Domain\Shared\Actions\ReportError;
use Exception;

final class UpdateContact
{
    /**
     * Update Contact Action Constructor.
     */
    public function __construct() {}

    /**
     * @param  array<string, string>  $data  (array of params)
     * @param  int  $id  ($contact->id)
     */
    public function execute(array $data, int $id): ?Contact
    {
        try {
            $contact = Contact::findOrFail($id);
            //Update Contact
            $contact->fill($data);
            if ($contact->update() && $contact instanceof Contact) {
                return $contact;
            }
            //Error handling
            ReportError::sendReport($data, 'Failed to update contact: ');
        } catch (Exception $e) {
            // if an exception occurred
            ReportError::sendReport($data, 'Exception: Failed to update contact: '.$e->getMessage(), $e);
        }

        return null;
    }
}
