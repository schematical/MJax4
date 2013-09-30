/*define( function () {*/
var MJaxControlBase = function(objData){
    //this.Data = objData;
    this.jEle = null;


    for(var strKey in objData){
        this[strKey] = objData[strKey];
    }
    for(var strKey in this.Events){
        for(var intIndex in this.Events[strKey]){
            var objEvent = this.Events[strKey][intIndex];
            $('body').on(
                objEvent.EventName,
                '#' + objEvent.ControlId,
                MJax.Actions[objEvent.Action._mclass]
            );
            //{"Type":"MJaxClickEvent","Rendered":false,"Once":false,"ControlId":"lnkTest","Selector":"#lnkTest","Action":{"Type":"MJaxServerControlAction","TargetControlId":null,"Function":"lnkTest_click","UseForm":true}}
        }
    }
    return this;
}
MJaxControlBase.prototype.Render = function(strText, strRender){
    if(typeof(this.Pre_Render) != 'undefined'){
        this.Pre_Render(strRender);
    }
    //if(this.jEle == null){

        //Create a new Ele
        var strHtml = window.Mustache.render(MJax.FormData.head.controls_tpls[this._mclass], this);

        return strHtml;
    /*}else{
        //update existing ele
    }*/
}
MJaxControlBase.prototype.Attach = function(){
    this.jEle = $('#'+ this.ControlId);
    this.jEle.data('mjax-control', this);
}
MJaxControlBase.prototype.SetValue = function(mixValue){ }
MJaxControlBase.prototype.GetValue = function(){
    return null;
}
MJaxControlBase.prototype.Update = function(objControl){
    if(objControl.Modified){
        //Re render?
        this.SetValue(objControl.Value);
    }
}
MJaxControlBase.prototype.MSerialize_before = function(){
    this.Value = this.GetValue();
}
MJaxControlBase.prototype.TriggerEvent = function(objEvent){ }
MJaxControlBase.prototype.Pre_Render = function(){}

MJax.ControlDefinitions['MJaxControlBase'] = MJaxControlBase;
/*
return MJaxControlBase;
});
*/

