
FixedMidashi JavaScript Library, version 1.11 (2018/12/03)

�T�v:
	FixedMidashi �́Atable �̌��o�����Œ肷�邽�߂� JavaScript �֐��ł��B 
	���ʂȃX�^�C���V�[�g�͕K�v����܂���B 
	�ڍׂ́Ahttp://hp.vector.co.jp/authors/VA056612/fixed_midashi/ ���Q�Ƃ��Ă��������B

�g�p���@:
	���o�����Œ肵���� table �� _fixedhead �������`���A
	FixedMidashi.create() ���Ăяo���Ă��������B

	�ȉ��̂Q�̃t�@�C��������܂����AMinified �������t�@�C�������g�����������B
	fixed_midashi.js:     Minified �������t�@�C��(35KB)
	fixed_midashi_src.js: Minified ������O�̃t�@�C��(100KB, UTF-8)

�g�p��:
	<script type="text/javascript" src="hoge/fixed_midashi.js"></script>

	<!-- body mode -->
	<body onLoad="FixedMidashi.create();">
	  <table _fixedhead="rows:1; cols:1">
	    ...

	<!-- div mode -->
	<style type="text/css" media="screen">
	  div.scroll_div { overflow: auto; }
	</style>
	<body onLoad="FixedMidashi.create();">
	  <div class="scroll_div">
	    ...

����m�F�u���E�U:
	�ȉ��̃u���E�U�œ���m�F���Ă��܂��B
		IE, Chrome, Firefox, Opera, Safari
	�{�v���O������2012�N�ɍ쐬���Ă���A����m�F�͓����̊e�u���E�U��
	�ŐV�o�[�W�����Ŏ��{���Ă��܂��B���̎��� OS �� WindowsXP �ł��B
	MAC, iOS, android �Ȃǂł��A�������̃u���E�U�ŊȒP�ȓ���m�F���s���Ă��܂��B

�A���C���X�g�[�����@:
	fixed_midashi.js, fixed_midashi_src.js ���폜���Ă��������B

�Ɛӎ���:
	�{�v���O�����̓t���[�\�t�g�E�F�A�ł��B 
	�{�v���O�����̏C���ł�z�z����s�ׂ͋֎~���܂��B 
	�{�v���O�����̏C���ł�g�ݍ��񂾃\�t�g�E�F�A��z�z���邱�Ƃ͖�肠��܂���B
	�{�v���O�����̎g�p�ɂ��A�����Ȃ鑹�Q�ɑ΂��Ă��A��҂͐ӔC�𕉂��܂���B 

��ҘA����:
	koiso@mxj.mesh.ne.jp

	����OS�ł��̃u���E�U���Ƃ����Ȃ��Ă��܂��A
	����ȃX�^�C����K�p����Ƃ����Ȃ��Ă��܂��A
	�Ƃ��������ۂ��������܂�����A�A������������Ƃ��肪�����ł��B

�X�V����:
	1.0 (2012/07/21) �������[�X
	1.1 (2013/05/03) �t�@�C�����y�ʉ�(Minified��)
	1.2 (2013/06/15) IE8���W�����[�h�̏ꍇ�̐��\���������P
	1.3 (2014/06/15) ���o�C���Ή�
		���o�C���[���ł́A�u���E�U����� onScroll �C�x���g�̒ʒm���x���A
		�X�N���[�����̌����ڂ������������߁APC�̏ꍇ�Ƃ͏����Ⴄ�����ɂ����B
	1.4 (2014/07/05) �Œ茩�o���̗񕝂̐ݒ菈�������P
	1.5 (2014/08/12) IE8���W�����[�h�̏ꍇ�̐��\�����P
		IE8���W�����[�h�̏ꍇ�ɋɒ[�ɒx����������������P�����B
	1.6 (2014/11/23) IE�́u�݊��\���v�Ή�
		IE�Łu�݊��\���v������ƌŒ茩�o�������������C�������B
	1.7 (2015/01/31) body-header-id �I�v�V�����ǉ�
	1.8 (2015/05/03) body-left-header-id, div-auto-size �I�v�V�����ǉ�
		div-auto-size �� div �̍����̎������T�C�Y��}�~���邱�Ƃɂ��A
		�u�������[�h�v�������ł���悤�ɂ���(�X�}�z�p)�B
	1.9 (2015/11/15)
		(1) box-shadow �I�v�V�����ǉ�
		(2) �ȉ��̏ꍇ�ɐ��퓮�삵�Ă��Ȃ������s����C��
		�E�擪�s��rowSpan��2�ȏ�̃Z��������table�ɑ΂��āArows(�Œ肷��s)��0�ɂ����ꍇ
		�E�Ώۂ�JavaScript�œ��I�ɐ�������table�ŁAtbody���Ȃ��ꍇ
	1.10 (2017/10/18)
		(1) class �I�v�V�����ǉ�
		(2) 2017/10/28���_�ōŐV�� chrome ���ƌ��o�����������ɑΉ�
	1.11 (2018/12/03)
		�Œ茩�o������ <input> �^�O������A���� type �� HTML5 �Œǉ����ꂽ "tel" �Ȃǂ̏ꍇ�ɂ��A
		�R�s�[�v�f�ƌ��v�f�̒l�𓯂��ɂ��邽�߂̐����ǉ�

--------
Copyright (C) 2012-2018 K.Koiso

