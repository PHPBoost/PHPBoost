
function Repository (url)
{
    if (url.charAt(url.length - 1) == '/')
        this.url = url + 'repository.xml';
    else
        this.url = url + '/repository.xml';
    
    this.apps = new Array();
    
    var xhr_object = null;
    if( window.XMLHttpRequest )     //Firefox
       xhr_object = new XMLHttpRequest();
    else if( window.ActiveXObject ) //Internet Explorer
       xhr_object = new ActiveXObject("Microsoft.XMLHTTP");
    
    xhr_object.open('GET', this.url, true);
    
    xhr_object.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
    xhr_object.send(data);
    
    xhr_object.onreadystatechange = function()
    {
        if( xhr_object.readyState == 1 )
            progress_bar(25, "{L_QUERY_LOADING}");
        else if( xhr_object.readyState == 2 )
            progress_bar(50, "{L_QUERY_SENT}");
        else if( xhr_object.readyState == 3 )
            progress_bar(75, "{L_QUERY_PROCESSING}");
        else if( xhr_object.readyState == 4 )
        {
            if( xhr_object.status == 200 )
            {
                progress_bar(100, "{L_QUERY_SUCCESS}");
                this.parse(xhr_object.responseText);
            }
            else
                progress_bar(99, "{L_QUERY_FAILURE}");
        }
    }
    xmlhttprequest_sender(xhr_object, null);
}

Repository.prototype.parse = function(xml)
{
    document.write(xml);
};

Repository.prototype.toString = function()
{
    return this.url;
};