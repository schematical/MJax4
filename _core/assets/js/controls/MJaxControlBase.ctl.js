/*define( function () {*/
var MJaxControlBase = function(objData){
    this.Data = objData;
    this.jEle = null;


    for(var strKey in this.Data){
        this[strKey] = this.Data[strKey];
    }
    for(var strKey in this.Events){
        for(var intIndex in this.Events[strKey]){
            var objEvent = this.Events[strKey][intIndex];
            $('body').on(
                objEvent.EventName,
                '#' + objEvent.ControlId,
                MJax.Actions[objEvent.Action.Type]
            );
            //{"Type":"MJaxClickEvent","Rendered":false,"Once":false,"ControlId":"lnkTest","Selector":"#lnkTest","Action":{"Type":"MJaxServerControlAction","TargetControlId":null,"Function":"lnkTest_click","UseForm":true}}
        }
    }
    return this;
}
MJaxControlBase.prototype.type = 'MJaxControlBase';
MJaxControlBase.prototype.Render = function(strText, strRender){
    if(this.jEle == null){
        //Create a new Ele
        var strHtml = window.Mustache.render(MJax.FormData.head.controls_tpls[this.Type], this);

        return strHtml;
    }else{
        //update existing ele
    }
}
MJaxControlBase.prototype.Attach = function(){
    if(this.jEle == null){
        this.jEle = $('#'+ this.ControlId);
        this.jEle.data('mjax-control', this);
    }
}
MJaxControlBase.prototype.SetValue = function(mixValue){ }
MJaxControlBase.prototype.GetValue = function(){
    return null;
}
MJaxControlBase.prototype.TriggerEvent = function(objEvent){ }

MJax.ControlDefinitions['MJaxControlBase'] = MJaxControlBase;
/*
return MJaxControlBase;
});
*/

