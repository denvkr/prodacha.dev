
function codeSave(){
    window.frame2.editor.save();
    window.frame2.document.forms.source_edit.submit();
}

function codeUndo(){
    window.frame2.editor.undo();
}

function codeRedo(){
    window.frame2.editor.redo();
}

function codeLine(){
    window.frame2.editor.execCommand.find(1);
}