<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api_m extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}



	// 삼성점 상담예약 데스크탑
	public function createConsultPcSs($data)
	{
		
		$this->CC_AUTHOR = $data['CC_AUTHOR'];
		$this->CC_PASSWORD = "1111";
		$this->CC_PN_DATE = $data['CC_PN_DATE'];
		$this->CC_MB_TELNUM = $data['CC_MB_TELNUM'];
		$this->CC_SUBJECT = "상담문의입니다.";
		$this->CC_CONTENT = $data['CC_CONTENT'];
		if ($data['CC_PRIVATE_INFO_AGREE'] == 'on') {
			$this->CC_PRIVATE_INFO_AGREE = true;	
		}else {
			$this->CC_PRIVATE_INFO_AGREE = $data['CC_PRIVATE_INFO_AGREE'];
		}
		$this->CC_PAGE_LOCK = true;
		$this->CC_REQ_IP = $this->input->ip_address();
		$this->OI_CODE = "OI00000121";	

		$this->db->trans_start();
        $this->db->insert('CC_CONSULT',$this);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }else{
        	// 삼성점 대표폰으로 문자전송
            // $this->sendSms($data);
            return true;
		}
	}
	// 청담점 상담예약 데스크탑
	public function createConsultPcCd($data)
	{
		
		$this->CC_AUTHOR = $data['CC_AUTHOR'];
		$this->CC_PASSWORD = "1111";
		$this->CC_PN_DATE = $data['CC_PN_DATE'];
		$this->CC_MB_TELNUM = $data['CC_MB_TELNUM'];
		$this->CC_SUBJECT = "상담문의입니다.";
		$this->CC_CONTENT = $data['CC_CONTENT'];
		if ($data['CC_PRIVATE_INFO_AGREE'] == 'on') {
			$this->CC_PRIVATE_INFO_AGREE = true;	
		}else {
			$this->CC_PRIVATE_INFO_AGREE = $data['CC_PRIVATE_INFO_AGREE'];
		}
		$this->CC_PAGE_LOCK = true;
		$this->CC_REQ_IP = $this->input->ip_address();
		$this->OI_CODE = "OI00000119";	

		$this->db->trans_start();
        $this->db->insert('CC_CONSULT',$this);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }else{
        	// 삼성점 대표폰으로 문자전송
            // $this->sendSms($data);
            return true;
		}
	}



	/**
	 * 신규 상담 신청
	 *
	 * @param [type] $request
	 * @return void
	 */
    public function createConsult($request)
    {
        $this->CC_AUTHOR = $request->CC_AUTHOR;
		$this->CC_PASSWORD = $request->CC_PASSWORD;
		$this->CC_PN_DATE = $request->CC_PN_DATE;
		$this->CC_MB_TELNUM = $request->CC_MB_TELNUM;
		$this->CC_SUBJECT = $request->CC_SUBJECT;
		$this->CC_CONTENT = $request->CC_CONTENT;
		$this->CC_PRIVATE_INFO_AGREE = $request->CC_PRIVATE_INFO_AGREE;
		$this->CC_PAGE_LOCK = $request->CC_PAGE_LOCK;
		$this->CC_REQ_IP = $this->input->ip_address();
		$this->OI_CODE = $request->OI_CODE;

		// $this->CC_AUTHOR = $request['CC_AUTHOR'];
		// $this->CC_PASSWORD = $request['CC_PASSWORD'];
		// $this->CC_PN_DATE = $request['CC_PN_DATE'];
		// $this->CC_MB_TELNUM = $request['CC_MB_TELNUM'];
		// $this->CC_SUBJECT = $request['CC_SUBJECT'];
		// $this->CC_CONTENT = $request['CC_CONTENT'];
		// $this->CC_PRIVATE_INFO_AGREE = $request['CC_PRIVATE_INFO_AGREE'];
		// $this->CC_PAGE_LOCK = $request['CC_PAGE_LOCK'];
		// $this->CC_REQ_IP = $this->input->ip_address();
		// $this->OI_CODE = $request['OI_CODE'];

        $this->db->trans_start();
        $this->db->insert('CC_CONSULT',$this);
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            return false;
        }else{
            return true;
		}
	}
	




	/**
	 * 기존 상담신청 리스트 가져오기
	 *
	 */
	public function getConsult($param)
	{
		$limit = 10; // 한페이지에 보여줄 목록 갯수
		$pageNum = $param['pageNum']; // 현재페이지 번호
		$offset = $limit * ($pageNum-1); // 몇번째부터 보여줄 것인가
		$keyword = $param['keyword'];
		$seq = $param['seq'];
		$oicode = $param['oicode']; // 상담신청시 등록했던 조리원 코드
		if($pageNum == null || $pageNum == "" || $pageNum == 0 || $pageNum == 1){
			$pageNum = 1;
			$offset = 0;
		} // 1페이지 클릭시 초기값

		$totCnt = $this->db->count_all_results('CC_CONSULT'); // 총 글 갯수
		$pageCnt = ceil($totCnt / $limit); // 한페이지에 보여줄 페이지 링크 갯수, 올림시킬것.

		if ($seq != null) {
			// return $this->db->get_where('CC_CONSULT', array('CC_SEQ'=>$seq))->row();
			return $this->db->get_where('CC_CONSULT', array('CC_SEQ'=>$seq))->row();
		} elseif($keyword != "") {
			$this->db->order_by('CC_REQ_DATE','DESC');
			$this->db->like('CC_SUBJECT',$keyword);
			
			$totCnt = $this->db->count_all_results('CC_CONSULT');
			
			$this->db->order_by('CC_REQ_DATE','DESC');
			$this->db->like('CC_SUBJECT',$keyword);

			$result = array(
				'queryResult' => $this->db->get('CC_CONSULT', $limit,$offset)->result()
			);
			
			$result['pageCnt'] = ceil($totCnt / $limit);
			// $result['limit'] = $limit;
			// $result['totCnt'] = $totCnt;

			return $result;
		} else {
			$this->db->order_by('CC_REQ_DATE','DESC');
			$result = array(
				'queryResult' => $this->db->get_where('CC_CONSULT',array('OI_CODE' => $oicode),$limit,$offset)->result(),
				'oicode' => $oicode
			);
			$this->db->where(array('OI_CODE' => $oicode));
			$this->db->from('CC_CONSULT');
			$totCnt =  $this->db->count_all_results();
			$result['pageCnt'] = ceil($totCnt / $limit);
			 
			return $result;
		}
	}


	/**
	 * 상담삭제
	 *
	 * @param [type] $seq
	 * @return boolean
	 * 
	 * 디비 트랜잭션값 체크필요함. 추후 개발.
	 */
	public function deleteConsult($seq)
	{

		$this->db->where('CC_SEQ',$seq);
		$this->db->delete('CC_CONSULT');
		return true;
	}





	/**
	 * 상담내용수정
	 *
	 * @param [type] $request
	 * @return void
	 */
	public function modifyConsult($request)
	{
		if ($request->CC_AUTHOR != null) {
			$this->CC_AUTHOR = $request->CC_AUTHOR;
			$this->CC_PASSWORD = $request->CC_PASSWORD;
			$this->CC_PN_DATE = $request->CC_PN_DATE;
			$this->CC_MB_TELNUM = $request->CC_MB_TELNUM;
			$this->CC_SUBJECT = $request->CC_SUBJECT;
			$this->CC_PAGE_LOCK = $request->CC_PAGE_LOCK;
			$this->CC_CONTENT = $request->CC_CONTENT;
			$this->CC_PRIVATE_INFO_AGREE = $request->CC_PRIVATE_INFO_AGREE;
	
			$this->db->where('CC_SEQ',$request->CC_SEQ);
			$this->db->update('CC_CONSULT',$this);
			return true;
		}
	}





	/**
	 * 상담 답변내용 등록
	 *
	 * @param [type] $request
	 * @return void
	 */
	public function registReply($request)
	{
		$this->CC_REPLY_CONTENT = $request->CC_REPLY_CONTENT;
		$this->CC_REPLY_STATE = $request->CC_REPLY_STATE;

		$this->db->where('CC_SEQ',$request->CC_SEQ);
		$this->db->update('CC_CONSULT',$this);

		return true;
	}





	/**
     * 문자전송 - 
     * 산후조리원에 신규상담신청 버튼 클릭 시 조리원 폰으로 문자전송
     * @return [type] [description]
     */
    public function sendSms($data)
    {
        include "third_party/suremcfg.php";
        include "third_party/common.php";
        

        //  $member         클라이언트측 일련번호
        //  $callphone1     호출번호 EX)"011"
        //  $callphone2     "234"
        //  $callphone3     "5678"
        //  $callmessage    80Byte
        //  $rdate          예약 날짜 EX) "20030617" 즉시 전송시 "00000000"
        //  $rtime          예약 시간 EX) "190000"   즉시 전송시 "000000"
        //  $reqphone1      회신번호 EX) "011"
        //  $reqphone2      회신번호 EX) "1111"
        //  $reqphone3      회신번호 EX) "1111"
        //  $callname       호출명

            
        // $packettest = new SuremPacket;

        
        // if ($rdate== "" || $rtime=="" )
        // {
        //     $rdate="00000000";
        //     $rtime="000000";
        // }

        // $result=$packettest->sendsms($member,$callphone1,$callphone2,$callphone3,$callmessage,$rdate,$rtime,$reqphone1,$reqphone2,$reqphone3,$callname);

        // $res =substr($result,94,1);

        // // 메시지 전송 성공
        // if ($res=="O") {



        // // 메시지 전송 실패    
        // }else {

        // }
            
    }





}    

