function Guest( id, title, firstName, lastName, tel, address, district, amphur, province ) {
    this.id = id;
    this.title = title;
    this.firstName = firstName;
    this.lastName = lastName;
    // optional
    this.email = "";
    this.tel = tel;
    this.address = address;
    this.district = district;
    this.amphur = amphur;
    this.province = province;
    // optional
    this.taxNumber = "";
    // optional
    this.otherDetails = "";
}
var form;
$( document ).ready( function() {
    form = new Form();
    form.initialise();
    form.createEditableFormButt();
    form.createConfirmButt();
    ( new Clock() ).initialise( "th" );
    // set all reveals
    createReloadPageButt();
    disableCloseOnBgReveal();
} );