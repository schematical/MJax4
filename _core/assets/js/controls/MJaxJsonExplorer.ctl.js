
var MJaxJsonExplorer = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);
    me.Pre_Render = function(){
        var objToRender = this.Value;
        var arrPath = this.Path.split('/');
        delete(arrPath[0]);
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
    me._OrigAttach = me.Attach;
    me.Attach = function(){
        this._OrigAttach();
        this.jEle.find('a').click(function(){
            var jThis = $(this).closest('.MJaxJsonExplorer');
            var objControl = jThis.data('mjax-control');
            objControl.Path += '/' + $(this).attr('data-key');
            objControl.Modified = true;
            //objControl.Update(objControl);
            $.mobile.changePage('mjax-touch');
            console.log("Success");
        });
    };

    return me;
}
//MJaxLinkButton.prototype.type = 'MJaxLinkButton';
MJax.ControlDefinitions['MJaxJsonExplorer'] = MJaxJsonExplorer;
