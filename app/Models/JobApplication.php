<?php

namespace App\Models;

use App\Core\Model;

class JobApplication extends Model
{
    protected $table = 'job_applications';

    public function findByUserId($userId, $orderBy = 'created_at DESC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, company_name, job_title, job_description, salary_range, 
                location, job_type, application_date, status, source, job_url, contact_name, contact_email, 
                contact_phone, interview_date, interview_type, notes, priority)
                VALUES (:user_id, :company_name, :job_title, :job_description, :salary_range, 
                :location, :job_type, :application_date, :status, :source, :job_url, :contact_name, :contact_email, 
                :contact_phone, :interview_date, :interview_type, :notes, :priority)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET 
                company_name = :company_name, job_title = :job_title, job_description = :job_description,
                salary_range = :salary_range, location = :location, job_type = :job_type,
                application_date = :application_date, status = :status, source = :source, job_url = :job_url,
                contact_name = :contact_name, contact_email = :contact_email, contact_phone = :contact_phone,
                interview_date = :interview_date, interview_type = :interview_type, notes = :notes, 
                priority = :priority, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query(
            "DELETE FROM {$this->table} WHERE id = :id",
            ['id' => $id]
        );
    }

    public function getByStatus($userId, $status)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND status = :status ORDER BY application_date DESC",
            ['user_id' => $userId, 'status' => $status]
        );
    }

    public function getStats($userId)
    {
        $result = $this->db->fetchOne(
            "SELECT 
                COUNT(*) as total,
                COUNT(CASE WHEN status = 'applied' THEN 1 END) as applied,
                COUNT(CASE WHEN status = 'interviewing' THEN 1 END) as interviewing,
                COUNT(CASE WHEN status = 'offered' THEN 1 END) as offered,
                COUNT(CASE WHEN status = 'rejected' THEN 1 END) as rejected,
                COUNT(CASE WHEN status = 'accepted' THEN 1 END) as accepted
             FROM {$this->table} WHERE user_id = :user_id",
            ['user_id' => $userId]
        );
        return $result ?: ['total' => 0, 'applied' => 0, 'interviewing' => 0, 'offered' => 0, 'rejected' => 0, 'accepted' => 0];
    }

    public function getUpcomingInterviews($userId, $days = 7)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} 
             WHERE user_id = :user_id 
             AND interview_date IS NOT NULL 
             AND interview_date >= CURRENT_DATE 
             AND interview_date <= CURRENT_DATE + INTERVAL ':days days'
             ORDER BY interview_date ASC",
            ['user_id' => $userId, 'days' => $days]
        );
    }

    public function getRecentApplications($userId, $limit = 5)
    {
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY created_at DESC LIMIT :limit",
            ['user_id' => $userId, 'limit' => $limit]
        );
    }

    public function updateStatus($id, $status)
    {
        return $this->db->query(
            "UPDATE {$this->table} SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id",
            ['id' => $id, 'status' => $status]
        );
    }
}
