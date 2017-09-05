<?php

class CategoriesModel extends CodeIgniter\Model {

    protected $table = 'categories';
    protected $primaryKey = 'id';
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'date'];
    protected $useTimestamps = false;
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public $order = 'DESC';
    
    // get total rows
    function total_rows() {
        $builder = $this->db->table($this->table);
        return $builder->countAll();
    }

}
