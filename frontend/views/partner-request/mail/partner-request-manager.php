<?php
/**
 * @author Albert Gainutdinov <xalbert.einsteinx@gmail.com>
 *
 * @var $partnerRequest array
 * @var $profile array
 */

?>

<h1>
    <?= Yii::t('shop', 'New partner request'); ?>
</h1>

<p>
    <b><?= Yii::t('shop', 'Contact person'); ?></b>: <?= $partnerRequest['contact_person']; ?>
</p>
<p>
    <b><?= Yii::t('shop', 'Company name'); ?></b>: <?= $partnerRequest['company_name']; ?>
</p>
<p>
    <b><?= Yii::t('shop', 'Website'); ?></b>: <?= $partnerRequest['website']; ?>
</p>
<p>
    <b><?= Yii::t('shop', 'Message'); ?></b>: <?= $partnerRequest['message']; ?>
</p>

<hr>

<?php if (Yii::$app->user->isGuest) : ?>
    <p>
        <b><?= Yii::t('shop', 'Name'); ?></b>: <?= $profile['name']; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Patronymic'); ?></b>: <?= $profile['patronymic']; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Surname'); ?></b>: <?= $profile['surname']; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Info'); ?></b>: <?= $profile['info']; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Phone number'); ?></b>: <?= $profile['phone']; ?>
    </p>
<?php else : ?>
    <p>
        <b><?= Yii::t('shop', 'Name'); ?></b>: <?= $profile->name; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Surname'); ?></b>: <?= $profile->surname; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Patronymic'); ?></b>: <?= $profile->patronymic; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Info'); ?></b>: <?= $profile->info; ?>
    </p>
    <p>
        <b><?= Yii::t('shop', 'Phone number'); ?></b>: <?= $profile->phone; ?>
    </p>
<?php endif; ?>
