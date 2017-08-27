/**
 * hiển thị một số có phân cách phần ngàn
 * ví dụ: 1000000 -> 1.000.000
 */
function numberWithCommas(x) {
    var parts = x.toString().split(",");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    return parts.join(",");
}
/**
 * 
 */
function unique(array){
    return array.filter(function(el, index, arr) {
        return index === arr.indexOf(el);
    });
}