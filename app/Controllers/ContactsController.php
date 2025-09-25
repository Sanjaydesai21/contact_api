<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class ContactsController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $query = $this->db->table('contacts')->get();
        $contacts = $query->getResultArray();

        echo json_encode([
            'status' => 'success',
            'data' => $contacts
        ]);
    }

    public function create()
    {
        $input = $this->getRequestInput();

        if (empty($input['name']) || empty($input['email']) || empty($input['phone'])) {
            echo json_encode(['status' => 'error', 'message' => 'Name, email, and phone are required']);
            return;
        }

        $data = [
            'name'       => $input['name'],
            'email'      => $input['email'],
            'phone'      => $input['phone'],
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->table('contacts')->insert($data);
        $id = $this->db->insertID();

        $contact = $this->db->table('contacts')->where('id', $id)->get()->getRowArray();

        echo json_encode([
            'status'  => 'success',
            'message' => 'Contact created successfully',
            'data'    => $contact
        ]);
    }

    public function show($id = null)
    {
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Contact ID is required']);
            return;
        }

        $contact = $this->db->table('contacts')->where('id', $id)->get()->getRowArray();

        if (!$contact) {
            echo json_encode(['status' => 'error', 'message' => 'Contact not found']);
            return;
        }

        echo json_encode(['status' => 'success', 'data' => $contact]);
    }

    public function update($id = null)
    {
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Contact ID is required']);
            return;
        }

        $contact = $this->db->table('contacts')->where('id', $id)->get()->getRowArray();
        if (!$contact) {
            echo json_encode(['status' => 'error', 'message' => 'Contact not found']);
            return;
        }

        $input = $this->getRequestInput();
        $data  = [];

        if (isset($input['name']))  $data['name'] = $input['name'];
        if (isset($input['email'])) $data['email'] = $input['email'];
        if (isset($input['phone'])) $data['phone'] = $input['phone'];

        if (empty($data)) {
            echo json_encode(['status' => 'error', 'message' => 'No data provided for update']);
            return;
        }

        $this->db->table('contacts')->where('id', $id)->update($data);

        $updated = $this->db->table('contacts')->where('id', $id)->get()->getRowArray();

        echo json_encode([
            'status'  => 'success',
            'message' => 'Contact updated successfully',
            'data'    => $updated
        ]);
    }

    public function delete($id = null)
    {
        if (!$id) {
            echo json_encode(['status' => 'error', 'message' => 'Contact ID is required']);
            return;
        }

        $contact = $this->db->table('contacts')->where('id', $id)->get()->getRowArray();
        if (!$contact) {
            echo json_encode(['status' => 'error', 'message' => 'Contact not found']);
            return;
        }

        $this->db->table('contacts')->where('id', $id)->delete();

        echo json_encode([
            'status'  => 'success',
            'message' => 'Contact deleted successfully'
        ]);
    }

    private function getRequestInput()
    {
        $input = $this->request->getJSON(true);
        if (empty($input)) {
            $input = $this->request->getRawInput();
        }
        return $input;
    }
}
