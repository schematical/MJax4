function MJaxAjaxConn(){
    this.target = document.location;
    return this;
}
MJaxAjaxConn.prototype.TriggerEvent = function(objData){
    //Async call out to server
    var objAjaxData =  {
        url: this.target.href,
        success: this.Success,
        data:objData,
        dataType:'json',
        error: this.Fail,
        type:'POST'
    };
    console.log(objAjaxData);
    var jXhr = $.ajax(
        objAjaxData
    );

}
MJaxAjaxConn.prototype.Success = function(objJson, strStatus, objXhr){
    MJax.Update(objJson);
}
MJaxAjaxConn.prototype.Fail = function(objJson, strStatus, objXhr){
    MJax.Error(objJson);
}
//Figure out fall back