
var MJaxForm = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);



    return me;
}
MJaxForm.prototype.type = 'MJaxForm';
MJax.ControlDefinitions['MJaxForm'] = MJaxForm;
