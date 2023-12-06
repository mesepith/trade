<?php 

if( !empty($js)){

	foreach ($js as $script) { ?>

		<script type="text/javascript" src="<?php echo base_url().$script ?>"></script>

<?php }} ?>

</body>
</html>