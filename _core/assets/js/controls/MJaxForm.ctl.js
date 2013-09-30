
var MJaxForm = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);
    me.body = MJax.Unserialize(objData.body);
    me.head = MJax.Unserialize(objData.head);
    delete(me.jEle);

    me.MSerialize_before = function(){ };
    me.Render = function(){
        MJax.LoadTemplate();
        var objRenderData = {};
        for(var strKey in MJax.FormData.body){
            objRenderData[strKey] = MJax.FormData.body[strKey];
        }
        objRenderData['_head'] = MJax.FormData.head;
        var strBody = window.Mustache.render(
            MJax.FormData.head.template_html,
            objRenderData
        );
        return strBody;
    }
    me.AttachControls = function(){
        for(var strId in  this.body){
            this.body[strId].Attach();
        }
    }
    return me;
}

MJax.ControlDefinitions['MJaxForm'] = MJaxForm;
