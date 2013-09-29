
var MJaxForm = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);
    me.body = MJax.Unserialize(objData.body);
    me.head = MJax.Unserialize(objData.head);
    delete(me.jEle);

    me.MSerialize_before = function(){ };

    return me;
}

MJax.ControlDefinitions['MJaxForm'] = MJaxForm;
