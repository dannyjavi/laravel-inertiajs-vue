export default function formatDate(date){

    let string = new Date(date)

    return string.toISOString().replace(/\//g,'-').split('T')[0]+' '+ string.toLocaleTimeString()
};