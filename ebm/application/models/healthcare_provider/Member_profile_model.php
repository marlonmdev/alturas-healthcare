<?php


class Member_profile_model extends CI_Model {

    public function find_member_profile($firstNameMember, $lastNameMember, $dateMember) {
        return $this->db->get_where('members', array('first_name' => $firstNameMember, 'last_name' => $lastNameMember, 'date_of_birth' => $dateMember))->row();
    }

    public function find_member_profile_by_id($healthCardNo) {
        $authA = $this->db->get_where('members', array('health_card_no' => $healthCardNo))->row();
        return $authA;
    }


    // public function get_itemCRUD(){
    //     if(!empty($this->input->get("search"))){
    //       $this->db->like('title', $this->input->get("search"));
    //       $this->db->or_like('description', $this->input->get("search")); 
    //     }
    //     $query = $this->db->get("items");
    //     return $query->result();
    // }


    // public function insert_item()
    // {    
    //     $data = array(
    //         'title' => $this->input->post('title'),
    //         'description' => $this->input->post('description')
    //     );
    //     return $this->db->insert('items', $data);
    // }


    // public function update_item($id) 
    // {
    //     $data=array(
    //         'title' => $this->input->post('title'),
    //         'description'=> $this->input->post('description')
    //     );
    //     if($id==0){
    //         return $this->db->insert('items',$data);
    //     }else{
    //         $this->db->where('id',$id);
    //         return $this->db->update('items',$data);
    //     }        
    // }


    // public function delete_item($id)
    // {
    //     return $this->db->delete('items', array('id' => $id));
    // }
}
