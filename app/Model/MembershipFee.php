<? class MembershipFee extends AppModel {

    var $actsAs = ['Containable'];

    var $belongsTo = array('User');
}