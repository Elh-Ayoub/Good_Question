    class Circular{
        arr = [];
        constructor(arr) {
            this.arr = arr;
        }
        get(index) {
            let i = index;
            while(i >= this.arr.length){
                i -= this.arr.length;
            }
            return this.arr[i];
        }
        size(){
            return this.arr.length;
        }
    }
    classes = ['bg-danger', 'bg-primary', 'bg-secondary', 'bg-success', 'bg-warning', 'bg-info', 'bg-light', 'bg-dark'];
    const bg = new Circular(classes);
    let categories = $('.categories');
    $('.categories').each(function(i, obj) {
        $('#' + obj.id).addClass(bg.get(i));        
    });
