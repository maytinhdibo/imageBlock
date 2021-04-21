{**
 * plugins/blocks/indexing/templates/block.tpl
 *
 * Copyright (c) 2014-2020 Simon Fraser University
 * Copyright (c) 2003-2020 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * Common site sidebar menu -- "Make a Submission" block.
 *}
<div class="pkp_block index-card" style="text-align: center">
    <h2 class="title">
        {$title}
    </h2>

    <div class="content-image">
        {$content}
    </div>
</div>
<style type="text/css">
    {literal}
    /* this is an intersting idea for this section */
    .content-image img {
        max-width: 100%;
        padding: 0;
        height: auto;
    }
    .content-image p{
        vertical-align: middle;
        text-align: center;
    }
    {/literal}
</style>