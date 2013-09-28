//define( function (MJaxTextBox) {
console.log(MJaxTextBox);
var MJaxTextBox = function(objData){

    var me = new MJax.ControlDefinitions.MJaxControlBase(objData);


    me.GetValue = function(){

    }
    me.SetValue = function(objValue){

    }
    return me;
}
MJaxTextBox.prototype.type = 'MJaxTextBox';
MJax.ControlDefinitions['MJaxTextBox'] = MJaxTextBox;
    //return MJaxTextBox;

//});