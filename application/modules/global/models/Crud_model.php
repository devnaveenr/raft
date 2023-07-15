<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Crud_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
		//$this->officeTbl = 'fti_office_details';
	}

	public function getAnyItems($data)
	{
		if (array_key_exists("select", $data) && !empty($data['select'])) {
			$this->db->select($data['select']);
		}
		$this->db->from($data['table']);
		if (array_key_exists("join", $data) && !empty($data['join'])) {
			foreach ($data['join'] as $join_table => $value1) {
				foreach ($value1 as $cond => $join_type) {
					$this->db->join($join_table, $cond, $join_type);
				}
			}
		}
		if (array_key_exists("where", $data) && !empty($data['where'])) {
			$this->db->where($data['where']);
		}
		if (array_key_exists("or_where", $data) && !empty($data['or_where'])) {
			$this->db->or_where($data['or_where']);
		}
		if (array_key_exists("group_by", $data) && !empty($data['group_by'])) {
			$this->db->group_by($data['group_by']);
		}
		if (array_key_exists("order_by", $data) && !empty($data['order_by'])) {
			$this->db->order_by($data['order_by']['column'], $data['order_by']['order']);
		}
		if (array_key_exists("like", $data) && !empty($data['like'])) {
			foreach ($data['like'] as $key => $value) {
				$this->db->like($key, $value);
			}
		}
		if (array_key_exists("or_like", $data) && !empty($data['or_like'])) {
			foreach ($data['or_like'] as $key => $value) {
				$this->db->or_like($key, $value);
			}
		}
		if (!empty($data['limit']) || !empty($data['start'])) {
			$this->db->limit($data['limit'], $data['start']);
		}

		if (!empty($data['output'])) {
			if ($data['output'] == "row_array") {
				$query = $this->db->get();
				return $query->row_array();
			} elseif ($data['output'] == "result_array") {
				$query = $this->db->get();
				return $query->result_array();
			} elseif ($data['output'] == "row_object") {
				$query = $this->db->get();
				return $query->row();
			} elseif ($data['output'] == "result_object") {
				$query = $this->db->get();
				return $query->result();
			} elseif ($data['output'] == "count") {
				$result = $this->db->count_all_results();
				return $result;
			}
		} else {
			$query = $this->db->get();
			return $query->result_array();
		}
	}

	public function insertItem($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function updateItem($table, $where, $data)
	{
		$is_update = $this->db->where($where)->update($table, $data);
		return $is_update;
	}

	public function deleteItems($table, $where = null)
	{
		if ($where) {
			$this->db->where($where);
		}
		return $this->db->delete($table);
	}

	public function getReports($data)
	{
		if (!empty($data['select'])) {
			$this->db->select($data['select']);
		}
		$this->db->from($data['table']);
		if (!empty($data['join'])) {
			foreach ($data['join'] as $joinkey => $joinvalue) {
				$this->db->join($joinvalue['table'], $joinvalue['condition'], $joinvalue['type']);
			}
		}
		if (!empty($data['where'])) {
			$this->db->where($data['where']);
		}
		if (array_key_exists("search", $data)) {
			// Filter data by searched keywords
			if (!empty($data['search']['keywords'])) {
				$this->db->group_start();

				$this->db->group_end();
			}
		}
		if (!empty($data['group_by'])) {
			$this->db->group_by($data['group_by']);
		}

		if (array_key_exists("order_by", $data)) {
			$this->db->order_by($data['order_by'], 'desc');
		}
		if (array_key_exists("start", $data) && array_key_exists("limit", $data)) {
			if ($data['limit'] != 0) {
				$this->db->limit($data['limit'], $data['start']);
			}
		} elseif (!array_key_exists("start", $data) && array_key_exists("limit", $data)) {
			if ($data['limit'] != 0) {
				$this->db->limit($data['limit']);
			}
		}
		if (!empty($data['output'])) {
			if ($data['output'] == "row_array") {
				$query = $this->db->get();
				return $query->row_array();
			} elseif ($data['output'] == "result_array") {
				$query = $this->db->get();
				return $query->result_array();
			} elseif ($data['output'] == "row_object") {
				$query = $this->db->get();
				return $query->row();
			} elseif ($data['output'] == "result_object") {
				$query = $this->db->get();
				return $query->result();
			} elseif ($data['output'] == "count") {
				$result = $this->db->count_all_results();
				return $result;
			}
		} else {
			$query = $this->db->get();
			return $query->result_array();
		}
	}

	public function getUnionReoirts($data)
	{
		$query = $this->db->query($data);
		return $query->result_array();
	}


}



/* End of file Crud_model.php */
/* Location: ./application/models/Crud_model.php */