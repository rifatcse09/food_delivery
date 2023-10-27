<?php

namespace App\Services;


use App\Models\RiderInfo;
use Illuminate\Database\Eloquent\Model;

class RiderService extends Service {


    /**
     * @return string
     */
    protected function getModel() : Model
    {
        return new RiderInfo();
    }

    /**
     * Update a bank record by ID with the provided data.
     *
     * @param int $id   The ID of the bank record to update.
     * @param array $data   The data to update the bank record with.
     * @return bool|\Exception   Returns true if the update is successful, or an exception object if an error occurs.
     */
    public function update(int $id, array $data) {

        try {
            $bank = $this->find($id);
            $bank->update($data);
            return true;
        } catch (\Exception $e) {
            return $e;
        }

    }

    /**
     * @param int $primaryKey
     * @return bool|\Exception
     */
    public function delete(int $primaryKey)
    {
        try {
            $this->find($primaryKey)->delete();
            return true;
        } catch (\Exception $e) {
            return $e;
        }
    }

    /**
     * Get the branches for a specific bank.
     *
     * @param int $bankId The ID of the bank.
     * @return \Illuminate\Database\Eloquent\Collection|\Exception The branches for the bank or an exception if an error occurs.
     */
    public function getBranches($bankId)
    {

        return $this->model->with('branches')->where(['bank_id' => $bankId])->get()->pluck('branches')->flatten();

    }
}
