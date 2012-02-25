<a href='#' onclick="return updateBooleanInlineEdit(this, <?php echo $value ?>, <?php echo $id ?>, '<?php echo url_for('@net7_advanced_generator_update_editinline_boolean_field?modelName=' . $modelName . '&fieldName=' . $field . '&relatedModel=' . $relatedModel) ?>');">
    <?php if ($value == 0): ?>
        <img alt="" src="/net7AdvancedGeneratorPlugin/images/cross.png" />
    <?php else: ?>
        <img alt="" src="/net7AdvancedGeneratorPlugin/images/check.png" />
    <?php endif; ?>
</a>

