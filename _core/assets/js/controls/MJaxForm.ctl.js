
var MJaxForm = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);
    me.body = MJax.Unserialize(objData.body);
    me.head = MJax.Unserialize(objData.head);
    delete(me.jEle);


    return me;
}

MJax.ControlDefinitions['MJaxForm'] = MJaxForm;
