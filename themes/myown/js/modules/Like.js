class Like {
    constructor() {
        this.event();
    }

    event() {
        $(".like-box").on("click", this.ourClickDispatcher.bind(this));
    }

    //methods
    ourClickDispatcher(e) {
        //in case I have multiple likes
        //in order to not confusing what I click
        var currentLikeBox = $(e.target).closest(".like-box");

        //$(".like-box").data('exists')=='yes'
        if(currentLikeBox.data('exists')=='yes') {
            this.deleteLike(currentLikeBox);
        } else {
            this.createLike(currentLikeBox);
        }
    }

    createLike(currentLikeBox) {
        $.ajax({
            beforeSend: (xhr) => {
                xhr.setRequestHeader('X-WP-Nonce', uniData.nonce);
            },
            url: uniData.root_url + '/wp-json/goapi/v1/manageLike',
            type: 'POST',
            data: {'professorId': currentLikeBox.data('professor')},
            success: (res) => {
                console.log(res);
            },
            error: (e) => {
                console.log(e)
            }
        });
    }

    deleteLike() {
        $.ajax({
            url: uniData.root_url + '/wp-json/goapi/v1/manageLike',
            type: 'DELETE',
            success: (res) => {
                console.log(res);
            },
            error: (e) => {
                console.log(e)
            }
        });
    }
}

const like = new Like();