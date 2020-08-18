<div class="container">
    <div class="update__settings-container" style="margin: 60px 0px;">
        <div class="form-group">
            <input type="text" class="form-control hook-st-form required" name="User[username]" required placeholder="ФИО">
        </div>


        <div class="flex-parent">
            <div class="input-flex-container">
                <div class="input active">
                    <span data-year="Телефон"></span>
                </div>
                <div class="input">
                    <span data-year="Паспортные данные"></span>
                </div>
                <div class="input">
                    <span data-year="Платежная информация"></span>
                </div>
            </div>
        </div>


        <div class="update__settings-container-hook">

            <div class="update__settings-item">
                <img id="update__preview1" class="update__preview-img" src="/images/update__pass.png" alt="">
                <span class="update__settings-preview__label">
                            Селфи с паспортом
                        </span>

                <input class="update__settings-input" type="file" onchange="readUrl(this)" name="pass_preview">
            </div>

            <div class="update__settings-item">
                <img id="update__preview2" class="update__preview-img" src="/images/update__pass2.png" alt="">
                <span class="update__settings-preview__label">
                            Лицевая сторона паспорта
                        </span>
                <input class="update__settings-input" type="file" onchange="readUrl(this)" name="pass_preview">
            </div>

            <div class="update__settings-item">
                <img id="update__preview2" class="update__preview-img" src="/images/update__pass3.png" alt="">
                <span class="update__settings-preview__label">
                            Прописка на паспорте
                        </span>
                <input class="update__settings-input" type="file" onchange="readUrl(this)" name="pass_preview">
            </div>

        </div>
    </div>

    <span class="stage__subline">
        Что бы пройти верификацию вам нужно
        загрузить все фотографии документов как показано на примере.
    </span>
    <button type="submit" class="btn btn-default m-40 update__settings-btn hook-stage">
        Далее
    </button>
</div>