
var MJaxJsonExplorer = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);
    me.Pre_Render = function(){
        var objToRender = this.Value;
        var arrPath = this.Path.split('/');
        arrPath.pop();
        for(var i in arrPath){
            objToRender = objToRender[arrPath[i]];
        }
        this.RenderData = [];
        for(var strKey in objToRender){
            if(typeof(objToRender[strKey]) != 'object'){
                var strText = objToRender[strKey];
            }else{
                var strText = typeof(objToRender[strKey]);
            }
            this.RenderData[this.RenderData.length] = {
                'name':strKey,
                'value':objToRender[strKey],
                'type':typeof(objToRender[strKey]),
                'text':strText
            };
        }
    }
    return me;
}
//MJaxLinkButton.prototype.type = 'MJaxLinkButton';
MJax.ControlDefinitions['MJaxJsonExplorer'] = MJaxJsonExplorer;
