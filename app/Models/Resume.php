<?php

namespace App\Models;

use App\Core\Model;

class Resume extends Model
{
    protected $table = 'resumes';

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
        $sql = "INSERT INTO {$this->table} (user_id, resume_name, file_path, file_type, version, is_default, target_role, summary)
                VALUES (:user_id, :resume_name, :file_path, :file_type, :version, :is_default, :target_role, :summary)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET 
                resume_name = :resume_name, file_path = :file_path, file_type = :file_type,
                version = :version, is_default = :is_default, target_role = :target_role, 
                summary = :summary, updated_at = CURRENT_TIMESTAMP
                WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function getDefault($userId)
    {
        return $this->db->fetchOne(
            "SELECT * FROM {$this->table} WHERE user_id = :user_id AND is_default = true",
            ['user_id' => $userId]
        );
    }

    public function setDefault($id, $userId)
    {
        $this->db->query("UPDATE {$this->table} SET is_default = false WHERE user_id = :user_id", ['user_id' => $userId]);
        return $this->db->query("UPDATE {$this->table} SET is_default = true WHERE id = :id", ['id' => $id]);
    }
}

class WorkExperience extends Model
{
    protected $table = 'work_experience';

    public function findByUserId($userId, $orderBy = 'start_date DESC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, company_name, job_title, location, start_date, end_date, is_current, description, achievements, skills_used)
                VALUES (:user_id, :company_name, :job_title, :location, :start_date, :end_date, :is_current, :description, :achievements, :skills_used)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET 
                company_name = :company_name, job_title = :job_title, location = :location,
                start_date = :start_date, end_date = :end_date, is_current = :is_current,
                description = :description, achievements = :achievements, skills_used = :skills_used
                WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }
}

class Education extends Model
{
    protected $table = 'education';

    public function findByUserId($userId, $orderBy = 'start_date DESC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, institution_name, degree, field_of_study, location, start_date, end_date, is_current, gpa, achievements)
                VALUES (:user_id, :institution_name, :degree, :field_of_study, :location, :start_date, :end_date, :is_current, :gpa, :achievements)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET 
                institution_name = :institution_name, degree = :degree, field_of_study = :field_of_study,
                location = :location, start_date = :start_date, end_date = :end_date, is_current = :is_current,
                gpa = :gpa, achievements = :achievements
                WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }
}

class Skill extends Model
{
    protected $table = 'skills';

    public function findByUserId($userId, $orderBy = 'category, skill_name', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, skill_name, skill_level, category, years_experience)
                VALUES (:user_id, :skill_name, :skill_level, :category, :years_experience)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET skill_name = :skill_name, skill_level = :skill_level, 
                category = :category, years_experience = :years_experience WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function getByCategory($userId)
    {
        return $this->db->fetchAll(
            "SELECT category, JSON_AGG(json_build_object('id', id, 'skill_name', skill_name, 'skill_level', skill_level, 'years_experience', years_experience)) as skills 
             FROM {$this->table} WHERE user_id = :user_id GROUP BY category",
            ['user_id' => $userId]
        );
    }
}

class Certification extends Model
{
    protected $table = 'certifications';

    public function findByUserId($userId, $orderBy = 'issue_date DESC', $limit = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_id = :user_id ORDER BY {$orderBy}";
        if ($limit) {
            $sql .= " LIMIT {$limit}";
        }
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function findById($id)
    {
        return $this->db->fetchOne("SELECT * FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO {$this->table} (user_id, certification_name, issuing_organization, issue_date, expiry_date, credential_id, credential_url)
                VALUES (:user_id, :certification_name, :issuing_organization, :issue_date, :expiry_date, :credential_id, :credential_url)";
        return $this->db->query($sql, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $sql = "UPDATE {$this->table} SET certification_name = :certification_name, issuing_organization = :issuing_organization,
                issue_date = :issue_date, expiry_date = :expiry_date, credential_id = :credential_id, credential_url = :credential_url
                WHERE id = :id";
        return $this->db->query($sql, $data);
    }

    public function delete($id)
    {
        return $this->db->query("DELETE FROM {$this->table} WHERE id = :id", ['id' => $id]);
    }
}
