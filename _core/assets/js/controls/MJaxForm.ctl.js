
var MJaxForm = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);
    me.body = MJax.Unserialize(objData.body);
    me.head = MJax.Unserialize(objData.head);


    return me;
}
MJaxForm.prototype.type = 'MJaxForm';
MJax.ControlDefinitions['MJaxForm'] = MJaxForm;
