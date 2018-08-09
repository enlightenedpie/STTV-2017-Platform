const parseParams = (str,regex) => {
    return (str || document.location.search).replace(/(^\?)/,'').replace(regex,'').split("&").map(function(n){return n = n.split("="),this[n[0]] = n[1],this}.bind({}))[0];
}
export default parseParams