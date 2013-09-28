
var MJaxTextBox = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);


    me.GetValue = function(){
        return this.jEle.val();
    }
    me.SetValue = function(strText){
        this.jEle.val(strText);
    }
    return me;
}
MJaxTextBox.prototype.type = 'MJaxTextBox';
MJax.ControlDefinitions['MJaxTextBox'] = MJaxTextBox;
